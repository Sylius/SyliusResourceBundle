<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('sr', array (
  'flashes' => 
  array (
    'sylius.resource.create' => '%resource% је успешно креиран.',
    'sylius.resource.update' => '%resource% је успешно ажуриран.',
    'sylius.resource.delete' => '%resource% је успешно обрисан.',
  ),
  'messages' => 
  array (
    'sylius.form.collection.delete' => 'Обриши',
  ),
));

$catalogueEn_US = new MessageCatalogue('en_US', array (
));
$catalogue->addFallbackCatalogue($catalogueEn_US);

return $catalogue;
