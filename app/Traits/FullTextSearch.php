<?php

namespace App\Traits;

trait FullTextSearch
{
    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    protected function fullTextWildcards($term,$contain_all = false)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);
        $words = explode(' ', $term);

        if(!$contain_all) {

            foreach ($words as $key => $word) {
                /*
                 * applying + operator (required word) only big words
                 * because smaller ones are not indexed by mysql
                 */
                if (strlen($word) >= 2) {
                    $words[$key] = '+' .$word.'*';
                }
            }

            $searchTerm = implode(' ', $words);
        }else {
            $searchTerm = '"'.implode(' ', $words).'"';
        }

        return $searchTerm;
    }

    /**
     * Scope a query that matches a full text search of term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term,$contain_all = false)
    {
        $columns = implode(',', $this->searchable);

//        dd($this->fullTextWildcards($term));

        $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));
        return $query;
    }
    //IN NATURAL LANGUAGE MODE
}