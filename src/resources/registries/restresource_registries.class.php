<?php
// This file is part of CyQ - https://github.com/boa-project
//
// CyQ is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// CyQ is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with CyQ.  If not, see <http://www.gnu.org/licenses/>.
//
// The latest code can be found at <https://github.com/boa-project/>.

//Include global resources
Restos::using('resources.registries.registry');
Restos::using('resources.registries.registries');

/**
 * Class to manage the registries
 *
 * @author David Herney <davidherney@gmail.com>
 * @package CyQ.Api
 * @copyright  2018 Congo y Quima Project
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero GPL v3 or later
 */
class RestResource_Registries extends RestResource {

    /**
    * When request verb is post
    * @see resources/RestResource::onPost()
    * @return bool
    */
    public function onPost(){
        $resources = $this->_restGeneric->RestReceive->getResources();

        if ($resources->isSpecificResources()){
            Restos::throwException(null, RestosLang::get('response.400.post.specificnotallowed'), 400);
        }

        $params = $this->_restGeneric->RestReceive->getProcessedContent();

        if (!isset($params['registryinfo'])) {
            Restos::throwException(null, RestosLang::get('registryinfoempty', 'cyq'), 400);
        }

        if (!$info = Registry::decipher($params['registryinfo'])) {
            Restos::throwException(null, RestosLang::get('badregistryinfo', 'cyq'), 400);
        }

        $registry = new Registry();

        $conditions = array('uuid' => $info->uuid);

        try {
            $registry->loadByFilter($conditions);
        }
        catch (ObjectNotFoundException $e) {
        }

        // It is a registered device.
        if ($registry->property_exists('id')) {
            if ($registry->password !== md5($info->password)) {
                Restos::throwException(null, RestosLang::get('badregistryinfopassword', 'cyq'), 403);
            }

            $data = new stdClass();
            $data->id = $registry->id;
            $data->token = Registry::getNewToken();

            if ($registry->username != $info->username) {
                $data->username = $info->username;
            }

            if ($registry->displayname != $info->displayname) {
                $data->displayname = $info->displayname;
            }

            $data->updated_at = time();
            $data->updated_by = User::$IsUserAuth ? User::id() : 0;

            $registry->save($data);

            $cypherpwd = md5($info->password);
        }
        else {
            $data = new stdClass();
            $data->uuid = $info->uuid;
            $data->password = md5($info->password);
            $data->username = $info->username;
            $data->displayname = $info->displayname;
            $data->token = Registry::getNewToken();

            $data->created_at = time();
            $data->updated_at = $data->created_at;
            $data->created_by = User::$IsUserAuth ? User::id() : 0;
            $data->updated_by = User::$IsUserAuth ? User::id() : 0;

            $registry->save($data);
            $cypherpwd = $data->password;
        }

        $token = Registry::cypher($data->token, $cypherpwd);

        $res = new stdClass();
        $res->token = $token;
        $mapping = new RestMapping($res);

        $this->_restGeneric->RestResponse->Content = $mapping->getMapping($this->_restGeneric->RestResponse->Type);

        return true;
    }

}
