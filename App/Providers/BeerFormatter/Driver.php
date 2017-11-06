<?php

namespace App\Providers\BeerFormatter;

interface Driver
{
    public function format($content);

}

/** This interface exists only as a Provider related contract ;) */