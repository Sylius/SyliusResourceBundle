<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('sr_CS', array (
  'flashes' => 
  array (
    'sylius.resource.create' => '%resource% je uspešno kreiran.',
    'sylius.resource.update' => '%resource% je uspešno ažuriran.',
    'sylius.resource.delete' => '%resource% je uspešno obrisan.',
  ),
  'messages' => 
  array (
    'sylius.form.collection.add' => 'Dodaj',
    'sylius.form.collection.delete' => 'Obrisati',
  ),
  'validators' => 
  array (
    'sylius.resource.not_enabled' => 'Traženi resurs je isključen',
    'sylius.resource.not_disabled' => 'Resurs je omogućen',
  ),
));

$catalogueSr = new MessageCatalogue('sr', array (
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
$catalogue->addFallbackCatalogue($catalogueSr);
$catalogueEn_US = new MessageCatalogue('en_US', array (
));
$catalogueSr->addFallbackCatalogue($catalogueEn_US);

return $catalogue;
