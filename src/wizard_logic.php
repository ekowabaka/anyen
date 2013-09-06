<?php
class wizard_logic
{
    /**
     * An instance of the wizard that the logic object can interract with.
     * @var Anyen
     */
    protected $wizard;
    
    public function setWizard($wizard)
    {
        $this->wizard = $wizard;
    }
}
