<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('oc', array (
  'pagerfanta' => 
  array (
    'Previous' => 'Precedent',
    'Next' => 'Seguent',
  ),
));

$catalogueEn_US = new MessageCatalogue('en_US', array (
));
$catalogue->addFallbackCatalogue($catalogueEn_US);

return $catalogue;
