<?php

namespace App\Model;

class View
{
    const PAGINATION = array(
        'max_pages_links' => 15,
        'items_per_page' => 5
    );

    public function getPaginationHeaderHTML($itemsCount)
    {
        $totalPages = ceil($itemsCount / self::PAGINATION['items_per_page']);
        return "<p style='margin: 0;text-align: center'>Total de itens encontrados:
                <b>$itemsCount</b></p>
                <p style='text-align: center'>Total de páginas: <b>$totalPages</b></p>";
    }

    public function getPaginationHTML($itemsCount, $currentPage)
    {
        $totalPages = ceil($itemsCount / self::PAGINATION['items_per_page']);
        $pageStart = 1;
        if ($currentPage >= self::PAGINATION['max_pages_links']) {
            $pageStart = $currentPage - self::PAGINATION['max_page_links'] + 2;
        }
        if ($currentPage == $totalPages && self::PAGINATION['max_page_links'] <= $totalPages) {
            $pageStart = $currentPage - self::PAGINATION['max_page_links'] + 1;
        }

        $html = '<ul class="pagination">
                <li class="page-item previous-page">
                    <a class="page-link" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>';
        for ($page = 0; $page < self::PAGINATION['max_pages_links']; $page++) {
            $pageNum = $pageStart + $page;
            $activeClass = $pageNum == $currentPage ? "active" : "";
            $html .= "<li class='page-item $activeClass' page='$pageNum'>
                        <a class='page-link'>$pageNum</a>
                    </li>";
        }
        $html .= '<li class="page-item next-page">
                    <a class="page-link" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                    </li>
                </ul>';

        return $html;
    }
}
