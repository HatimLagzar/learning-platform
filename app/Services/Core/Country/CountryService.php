<?php

namespace App\Services\Core\Country;

use App\Models\Country;
use App\Repositories\Country\CountryRepository;
use Illuminate\Database\Eloquent\Collection;

class CountryService
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @return Country[]|Collection
     */
    public function getAll(): Collection
    {
        return $this->countryRepository->getAll();
    }
}
