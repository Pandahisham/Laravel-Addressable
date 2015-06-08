<?php

namespace DraperStudio\Addressable;

trait Addressable
{
    /**
     * Return collection of addresses related to the tagged model.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Fetch primary address.
     *
     * @return Address
     */
    public function primaryAddress($address)
    {
        if (!empty($address)) {
            $address->update([
                'is_primary' => 1, 'is_billing' => 0, 'is_shipping' => 0,
            ]);
        }

        return $this->addresses()->orderBy('is_primary', 'DESC')->firstOrFail();
    }

    /**
     * Fetch billing address.
     *
     * @return Address
     */
    public function billingAddress($address)
    {
        if (!empty($address)) {
            $address->update([
                'is_primary' => 0, 'is_billing' => 1, 'is_shipping' => 0,
            ]);
        }

        return $this->addresses()->orderBy('is_billing', 'DESC')->firstOrFail();
    }

    /**
     * Fetch shipping address.
     *
     * @return Address
     */
    public function shippingAddress($address)
    {
        if (!empty($address)) {
            $address->update([
                'is_primary' => 0, 'is_billing' => 0, 'is_shipping' => 1,
            ]);
        }

        return $this->addresses()->orderBy('is_shipping', 'DESC')->firstOrFail();
    }

    /**
     * @param $data
     *
     * @return object
     */
    public function createAddress($data)
    {
        return $this->addresses()->save(new Address($data));
    }

    /**
     * @param $address
     * @param $data
     *
     * @return object
     */
    public function updateAddress($address, $data)
    {
        return $address->update($data);
    }

    /**
     * @param $address
     *
     * @return mixed
     */
    public function deleteAddress($address)
    {
        return $address->delete();
    }
}
