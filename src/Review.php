<?php

namespace App;

use DateTime;

class Review
{
    public ?int $id;
    public int $guestId;
    public int $rating;
    public string $review;
    public DateTime $date;

    public function __construct(?int $id, int $guestId, int $rating, string $review, DateTime $date)
    {
        $this->id = $id;
        $this->guestId = $guestId;
        $this->rating = $rating;
        $this->review = $review;
        $this->date = $date;
    }
}