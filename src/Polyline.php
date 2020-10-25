<?php

namespace Lassehaslev\NovaMapFields;

class Polyline extends MapField
{
    /**
     * Create a new field.
     * And set default properties.
     *
     * @param string      $name
     * @param string|null $attribute
     * @param mixed|null  $resolveCallback
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        $this->help('Click on the map to create a new point. Drag a marker to change the point. When the map is selected, you can press [backspace] to remove markers.');

        parent::__construct($name, $attribute = null, $resolveCallback);
    }

    /**
     * Resolve the attribute before sending to frontend.
     *
     * @param mixed      $resource
     * @param mixed|null $attribute
     *
     * @return array
     */
    public function resolveAttribute($resource, $attribute = null)
    {
        return json_decode($resource->{$attribute});

        return [
            'lat' => $resource->{$this->meta['latitudeField']},
            'lng' => $resource->{$this->meta['longitudeField']},
        ];
    }
}
