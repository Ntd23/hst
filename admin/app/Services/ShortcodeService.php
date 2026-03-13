<?php 
namespace App\Services;

use App\Shortcode\Core\ShortcodeManager;
class ShortcodeService
{
    public function __construct(
        protected ShortcodeManager $manager
    ) {}
    public function allShortcode($content,$locale){
        return $this->manager->getShortcode($content,$locale);
    }
    
}