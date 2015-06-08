<?php

namespace DraperStudio\Addressable;

use Illuminate\Database\Eloquent\Model;
use DraperStudio\Countries\Models\Country;

class Address extends Model
{
    /**
     * @var string
     */
    protected $table = 'addresses';

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function addressable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($address) {
            if (config('draperstudio.addresses.geocode')) {
                $address->geocode();
            }

            if (empty($address->country_id)) {
                $defaultCountry = config('draperstudio.addresses.default_country');

                $country = Country::where('cca2', '=', $defaultCountry)->first(['id']);

                $address->country_id = $country->id;
            }
        });
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return sprintf(
            '%s %s %s %s', $this->name_prefix, $this->name_suffix,
            $this->first_name, $this->last_name
        );
    }

    /**
     * @return string
     */
    public function getAddressAttribute()
    {
        return sprintf('%s, %s %s', $this->city, $this->state, $this->postcode);
    }

    /**
     * @return $this
     */
    public function geocode()
    {
        if (!empty($this->postcode)) {
            $string[] = $this->street;
            $string[] = sprintf('%s, %s %s', $this->city, $this->state, $this->postcode);
            $string[] = $this->country_name;
        }

        $query = str_replace(' ', '+', implode(', ', $string));

        $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$query.'&sensor=false');
        $output = json_decode($geocode);

        if (count($output->results)) {
            $this->latitude = $output->results[0]->geometry->location->lat;
            $this->longitude = $output->results[0]->geometry->location->lng;
        }

        return $this;
    }
}
