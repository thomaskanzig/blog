<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GalleryExtension extends AbstractExtension
{

    const SIZES = [
        'column-1-card-1' => 'gallery_600x770',
        'column-1-card-2' => 'gallery_600x300',
        'column-1-card-3' => 'gallery_600x530',
        'column-1-card-4' => 'gallery_600x400',
        'column-2-card-1' => 'gallery_600x530',
        'column-2-card-2' => 'gallery_600x300',
        'column-2-card-3' => 'gallery_600x770',
        'column-2-card-4' => 'gallery_600x400',
        'column-3-card-1' => 'gallery_600x770',
        'column-3-card-2' => 'gallery_600x300',
        'column-3-card-3' => 'gallery_600x770',
        'column-3-card-4' => 'gallery_600x300',
        'column-4-card-1' => 'gallery_600x530',
        'column-4-card-2' => 'gallery_600x300',
        'column-4-card-3' => 'gallery_600x770',
        'column-4-card-4' => 'gallery_600x400',
    ];

    public function getFunctions()
    {
        return [
            new TwigFunction('gallerySize', [$this, 'getSize']),
        ];
    }

    /**
     * Find correct size for the position.
     *
     * @param int $indexBlock
     *
     * @return string
     */
    public function getSize(int $indexBlock)
    {
        $totalOfColumns = 4;
        $totalOfTypes = 4;

        $column = $indexBlock % $totalOfColumns;
        if (0 === $column) {
            $column = $totalOfColumns;
        }

        $itemOfColumn = (($indexBlock - $column) / $totalOfColumns) + 1;
        $itemOfColumn = $itemOfColumn % $totalOfTypes;

        if (0 === $itemOfColumn) {
            $itemOfColumn = $totalOfTypes;
        }

        return self::SIZES['column-' . $column . '-card-' . $itemOfColumn];
    }
}
