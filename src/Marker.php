<?php

namespace Lassehaslev\NovaMapFields;

use Laravel\Nova\Http\Requests\NovaRequest;

class Marker extends MapField
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
        $this->help('Drag the marker to change the point.');

        parent::__construct($name, $attribute = null, $resolveCallback);
    }

    /**
     * Set the latutude field.
     *
     * @param mixed $fieldName
     *
     * @return $this
     */
    public function setLatitude($fieldName)
    {
        return $this->withMeta([
            'latitudeField' => $fieldName,
        ]);
    }

    /**
     * Set the longitude field.
     *
     * @param mixed $fieldName
     *
     * @return $this
     */
    public function setLongitude($fieldName)
    {
        return $this->withMeta([
            'longitudeField' => $fieldName,
        ]);
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
        return [
            'lat' => $resource->{$this->meta['latitudeField']},
            'lng' => $resource->{$this->meta['longitudeField']},
        ];
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param string                                  $requestAttribute
     * @param object                                  $model
     * @param string                                  $attribute
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $latLng = json_decode($request[$requestAttribute]);

            $model->{$this->meta['latitudeField']} = $latLng->lat;
            $model->{$this->meta['longitudeField']} = $latLng->lng;
        }
    }
}
