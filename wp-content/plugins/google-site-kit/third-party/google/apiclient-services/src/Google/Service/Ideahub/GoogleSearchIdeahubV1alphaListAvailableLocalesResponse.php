<?php

namespace Google\Site_Kit_Dependencies;

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
class Google_Service_Ideahub_GoogleSearchIdeahubV1alphaListAvailableLocalesResponse extends \Google\Site_Kit_Dependencies\Google_Collection
{
    protected $collection_key = 'availableLocales';
    protected $availableLocalesType = 'Google\Site_Kit_Dependencies\Google_Service_Ideahub_GoogleSearchIdeahubV1alphaAvailableLocale';
    protected $availableLocalesDataType = 'array';
    public $nextPageToken;
    /**
     * @param Google_Service_Ideahub_GoogleSearchIdeahubV1alphaAvailableLocale[]
     */
    public function setAvailableLocales($availableLocales)
    {
        $this->availableLocales = $availableLocales;
    }
    /**
     * @return Google_Service_Ideahub_GoogleSearchIdeahubV1alphaAvailableLocale[]
     */
    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }
    public function setNextPageToken($nextPageToken)
    {
        $this->nextPageToken = $nextPageToken;
    }
    public function getNextPageToken()
    {
        return $this->nextPageToken;
    }
}
