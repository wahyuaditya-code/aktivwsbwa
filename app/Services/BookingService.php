<?php

namespace App\Services;

use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\WorkshopRepositoryInterface;

use App\Models\BookingTranscaction;
use App\Models\WorkshopParticipant;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    protected $bookingRepository;
    protected $workshopRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository,
     WorkshopRepositoryInterface $workshopRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->workshopRepository = $workshopRepository;
    }

    public function storeBooking(array $validatedData)
    {
        //ambil data dari session di BookingRepository
        $existingData= $this->bookingRepository->getOrderDataFromSession();
        

        // menyamakan data dan merge data untuk di updated
        $updatedData= array_merge($existingData, $validatedData);

        // save data to session
        $this->bookingRepository->saveToSession($updatedData);

        return $updatedData;
    }

    public function isBookingSessionAvailable()
    {
        return $this->bookingRepository->getOrderDataFromSession() !== null;
    }

    public function getBookingDetails()
    {
        //ambil data dari session
        $orderData = $this->bookingRepository->getOrderDataFromSession();

        //pengecekan data dari session if kosong return ke index, else lanjut booking
        if(empty($orderData)){
            return null;
        }

        $workshop = $this->workshopRepository->find($orderData['workshop_id']);

        //apakah ada quantity di session menggunakan isset;
        $quantity = isset($orderData['quantity']) ? $orderData['quantity'] : 1;
        $subTotalAmount = $workshop->price * $quantity;

        $taxRate = 0.11;
        $totalTax = $subTotalAmount * $taxRate;

        $totalAmount= $totalTax + $subTotalAmount;

        //memasukkan kembali data yang telah di logic ke session
        $orderData['sub_total_amount'] = $subTotalAmount;
        $orderData['total_tax'] = $totalTax;
        $orderData['total_amount'] = $totalAmount;

        //kirim orderData ke class Repositories/BookingRepository kirim session
        $this->bookingRepository->saveToSession($orderData); 

        return compact('orderData', 'workshop');

    }

    public function finalizeBookingAndPayment(array $paymentData)
    {
        $orderData= $this->bookingRepository->getOrderDataFromSession();

        //cek dan kasih pesan error
        if(!$orderData){
            throw new \Exception('Booking data is missing from session.');
        }

        Log::info('Order Data : ' , $orderData); // add this line to log the orderData

        if(!isset($orderData['total_amount'])){
            throw new \Exception('Total Amount is missing from the order data.');
        }

        if(isset($paymentData['proof'])){
            $proofPath = $paymentData['proof']->store('proofs', 'public');
        }

        DB::beginTransaction();

        try {
            $bookingTranscaction = BookingTransaction::create([
                'name' => $orderData['name'],
                'email' => $orderData['email'],
                'phone' => $orderData['phone'],
                'customer_bank_name' => $paymentData['customer_bank_name'],
                'customer_bank_number' => $paymentData['customer_bank_number'],
                'customer_bank_account' => $paymentData['customer_bank_account'],
                'poof' => $proofPath,
                'quantity' => $orderData['quantity'],
                'total_amount' => $orderData['total_amount'],
                'is_paid' => false,
                'workshop_id' => $orderData['workshop_id'],
                'booking_trx_id' => BookingTransaction::generateUniqueTrxId(),
            ]);

            //create workshop participants
            foreach ($orderData['participant'] as $participant) {
                WorkshopParticipant::create([
                    'name' => $participant['name'],
                    'ocupation' => $participant['ocupation'],
                    'email' => $participant['email'],
                    'workshop_id' => $bookingTranscaction->workshop_id,
                    'booking_transaction_id' => $bookingTranscaction->id,
                ]);
            }
            //commit the transaction
            DB::commit();

            //clear the session data after successful booking
            $this->bookingRepository->clearSession();

            //return the booking transaction ID for redirect
            return $bookingTranscaction->id;

        } catch( \Exception $e) {
            //log the exception for debugging
            Log::error('Payment processing failed :'. $e->getMessage());

            //rollback the tanssaction in case of any errors
            DB::rollBack();

            //retrhow the exception to be handled by the controller
            throw $e;
        }

    }
    public function getMyBookingDetails(array $validated)
    {
        return $this->bookingRepository->findByTrxIdAndPhoneNumber($validated['booking_trx_id'],
        $validated['phone']);
    }

}