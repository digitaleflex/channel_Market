<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\PhoneNormalizerService;
use Tests\TestCase;

class PhoneNormalizerServiceTest extends TestCase
{
    private PhoneNormalizerService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PhoneNormalizerService;
    }

    public function test_normalizes_benin_phone_number(): void
    {
        $result = $this->service->normalize('+229 97 12 34 56');

        $this->assertEquals('BJ', $result['country_code']);
        $this->assertEquals('97123456', $result['number']);
    }

    public function test_normalizes_senegal_phone_number(): void
    {
        $result = $this->service->normalize('+221 77 123 45 67');

        $this->assertEquals('SN', $result['country_code']);
        $this->assertEquals('771234567', $result['number']);
    }

    public function test_normalizes_ivory_coast_phone_number(): void
    {
        $result = $this->service->normalize('+225 07 08 09 10 11');

        $this->assertEquals('CI', $result['country_code']);
        $this->assertEquals('0708091011', $result['number']);
    }

    public function test_normalizes_france_phone_number(): void
    {
        $result = $this->service->normalize('+33 6 12 34 56 78');

        $this->assertEquals('FR', $result['country_code']);
        $this->assertEquals('612345678', $result['number']);
    }

    public function test_normalizes_usa_phone_number(): void
    {
        $result = $this->service->normalize('+1 555 123 4567');

        $this->assertEquals('US', $result['country_code']);
        $this->assertEquals('5551234567', $result['number']);
    }

    public function test_falls_back_to_default_country_when_no_dial_code(): void
    {
        $result = $this->service->normalize('0612345678', 'FR');

        $this->assertEquals('FR', $result['country_code']);
        $this->assertEquals('0612345678', $result['number']);
    }
}
