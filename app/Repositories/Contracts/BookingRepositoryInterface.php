<?php

namespace App\Repositories\Contracts;

interface BoookingRepositoryInterface
{
    public function createBooking(array $data);

    public function findByTrxIdAndPhoneNumber($bookingTrxId, $phoneNumber);

    public function saveToSession(array $data);
    public function updateSessionData(array $data);
    public function getOrderDataFromSession();
    public function clearSession();

}