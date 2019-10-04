<?php

namespace onefasteuro\Stamped;

use Carbon\Carbon;

class StampedHelper
{

    /**
     * Get json ld output for the reviews
     * @param array $reviews
     * @return false|string|void
     */
    public static function toJsonLd(array $reviews, $summary, $total)
    {
        if(count($reviews) == 0) {
            return;
        }


        $formatDate = function ($date) {
            $d = new Carbon($date);
            return $d->format('M d, Y');
        };

        $output = [];

        foreach($reviews as $k => $v) {
            $output[] = [
                '@type' => 'Review',
                'name' => $v['reviewTitle'],
                'datePublished' => $formatDate($v['dateCreated']),
                'author' => $v['author'],
                'reviewBody' => $v['reviewMessage'],
                'reviewRating' => [
                    '@type' => 'Rating',
                    'ratingValue' => $v['reviewRating'],
                ]
            ];
        }

        $data = [
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => $summary,
                'reviewCount' => $total
            ],
            'review' => $output
        ];

        $return = json_encode($data, JSON_PRETTY_PRINT);
        $return = ltrim($return, '{');
        $return = rtrim($return, '}');
        $return = trim($return);


        return $return;
    }

}
