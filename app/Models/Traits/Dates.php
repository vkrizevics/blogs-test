<?php

namespace App\Models\Traits;

use DateTime;

trait Dates {
    public function getCreatedAtFormatted()
    {
        $converter = DateTime::createFromFormat('Y-m-d H:i:s', $this->created_at);

        if ($converter) {
            $ret = $converter->format('d.m.Y. H:i');
        } else {
            $ret = $this->created_at;
        }

        return $ret;
    }
}
