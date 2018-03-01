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

/**
 * Class to manage the registries mapping
 *
 * @author David Herney <davidherney@gmail.com>
 * @package CyQ.Api
 * @copyright  2018 Congo y Quima Project
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */
class RestMapping_Registries extends RestMapping {

    /**
     * 
     * Construct
     * @param object or array $data
     */
    public function __construct($data) {
        parent::__construct($data, "registry", "registries");
    }

}
