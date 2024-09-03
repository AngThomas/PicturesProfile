<?php

namespace App\Interface;

use JMS\Serializer\Annotation as Serializer;

#[Serializer\ExclusionPolicy('all')]
interface JmsSerializable
{
}