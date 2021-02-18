<?php declare(strict_types=1);

namespace App\Entity;

trait DateTimeTrait
{
    protected function formatForDatabase(\DateTimeInterface $datetime): \DateTimeImmutable
    {
        $datetime = clone $datetime;
        $datetime->setTimezone(new \DateTimeZone('UTC'));
        return \DateTimeImmutable::createFromInterface($datetime);
    }

    protected function formatFromDatabase(\DateTimeInterface $datetime): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat(
            'Y-m-d\TH:i:s.v',
            $datetime->format('Y-m-d\TH:i:s.v'),
            new \DateTimeZone('UTC')
        );
    }
}
