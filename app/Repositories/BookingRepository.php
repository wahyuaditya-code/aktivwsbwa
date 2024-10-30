<?php

namespace App\Repositories;

use App\Models\BookingTransaction;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Facades\Session;

class BookingRepository implements BookingRepositoryInterface
{

    public function createBooking(array $data)
    {
        return BookingTranscaction::create($data);
    }


    public function findByTrxIdAndPhoneNumber($bookingTrxId, $phoneNumber)
    {
        return BookingTransaction::where('booking_trx_id', $bookingTrxId)
                                ->where('phone', $phoneNumber)
                                ->fisrt();
    }

    public function saveToSession()
    {
        Session::put('orderData', $data);
    }

    public function getOrderDataFromSession()
    {
        return Session::get('orderData', []);
    }

    public function updateSessionData(array $data)
    {
        $orderData= session('orderData', []); //ambil data
        $orderData = array_merge($orderData, $data); //gabungkan data
        session(['orderData' => $orderData]); //ambil kembali data
    }

    public function clearSession(){
        Session::forget('orderData');
    }

}