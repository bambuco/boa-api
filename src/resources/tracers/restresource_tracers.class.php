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
Restos::using('resources.tracers.tracer');
Restos::using('resources.tracers.tracers');
Restos::using('resources.registries.registry');

/**
 * Class to manage the tracers
 *
 * @author David Herney <davidherney@gmail.com>
 * @package CyQ.Api
 * @copyright  2018 Congo y Quima Project
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero GPL v3 or later
 */
class RestResource_Tracers extends RestResource {

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

        if (!isset($params['traceinfo'])) {
            Restos::throwException(null, RestosLang::get('traceinfoempty', 'cyq'), 400);
        }

        if (!isset($params['securityinfo'])) {
            Restos::throwException(null, RestosLang::get('securityinfoempty', 'cyq'), 400);
        }

        if (!($info = Tracer::decipher($params['securityinfo'])) ||
                !property_exists($info, 'uuid') ||
                !property_exists($info, 'token')||
                !property_exists($info, 'current_call')) {
            Restos::throwException(null, RestosLang::get('badtraceinfo', 'cyq'), 400);
        }

        $registry = new Registry();
        $conditions = array('uuid' => $info->uuid, 'token' => $info->token);

        try {
            $registry->loadByFilter($conditions);
        }
        catch (ObjectNotFoundException $e) {
            // It is not a registered device.
            Restos::throwException(null, RestosLang::get('notregistered', 'cyq'), 400);
        }

        if ($registry->current_call >= $info->current_call) {
            Restos::throwException(null, RestosLang::get('currentcall_incorrect', 'cyq'), 400);
        }

        $tracer = new Tracer();

        $trace_info = json_decode($params['traceinfo']);

        $data = new stdClass();
        $data->registry_id = $registry->id;

        $fields = array('level', 'challenge', 'duration', 'score', 'win_score', 'correct_answers', 'user_answers', 'answer_at');
        foreach($fields as $key) {
            if (property_exists($trace_info, $key)) {
                $data->$key = $trace_info->$key;
            }
            else {
                Restos::throwException(null, RestosLang::get('traceemptyfield', 'cyq', $key), 400);
            }
        }

        // Because is an array but is saved as string.
        $data->correct_answers = json_encode($data->correct_answers);
        $data->user_answers = json_encode($data->user_answers);

        $data->created_at = time();
        $data->updated_at = $data->created_at;
        $data->created_by = User::$IsUserAuth ? User::id() : 0;
        $data->updated_by = User::$IsUserAuth ? User::id() : 0;

        if (!$tracer->validate($data, false, $badfields)){
            $detail = json_encode($badfields);
            Restos::throwException(null, RestosLang::get('save.baddata.detail', 'restos', $detail), 400);
        }

        $res = $tracer->save();
        if($res) {
            $registry->current_call = $info->current_call + 1;
            $registry->updated_at = time();
            $registry->updated_by = User::$IsUserAuth ? User::id() : 0;
            $registry->save();

            $res = new stdClass();
            $res->id = $tracer->id;
            $mapping = new RestMapping($res);
            $this->_restGeneric->RestResponse->Content = $mapping->getMapping($this->_restGeneric->RestResponse->Type);
        }
        else {
            $this->_restGeneric->RestResponse->setHeader(HttpHeaders::$STATUS_CODE, HttpHeaders::getStatusCode('500'));
            $this->_restGeneric->RestResponse->Content = 0;
        }

        return true;
    }

}
