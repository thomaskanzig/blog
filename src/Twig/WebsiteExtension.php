<?php
namespace App\Twig;

use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class WebsiteExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var Setting
     */
    protected $setting;

    /**
     * WebsiteExtension constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        $this->setting = $this->em->getRepository(Setting::class)
            ->findOne();
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('setting', [$this, 'getSetting']),
        ];
    }

    /**
     * @return Setting
     */
    public function getSetting()
    {
        return $this->setting;
    }
}