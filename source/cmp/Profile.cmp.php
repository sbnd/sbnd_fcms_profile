<?php
/**
* SBND F&CMS - Framework & CMS for PHP developers
*
* Copyright (C) 1999 - 2013, SBND Technologies Ltd, Sofia, info@sbnd.net, http://sbnd.net
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* @author SBND Techologies Ltd <info@sbnd.net>
* @package cms.cmp.profile
* @version 1.1
*/

BASIC::init()->imported("Profiles.cmp", "cms/controlers/back");
class Profile extends Profiles{
   /**
    * Main function - the constructor of the component
    * @access public
    * @return void
    */
	function main(){
		parent::main();
		
		$this->id = BASIC_USERS::init()->getUserId();
		if(!$this->id ){
			BASIC_URL::init()->redirect(BASIC::init()->virtual());
		}
		
		$this->unsetField(BASIC_USERS::init()->level_column);
		$this->unsetField(BASIC_USERS::init()->perm_column);
		$this->unsetField('page_max_rows');
	//	$this->unsetField('language');
				
		$this->updateField('countries', array(
			'attributes' => array(
				'data' => $this->getCountries()
			)
		));
	
        $this->updateField('language', array(
        	'attributes' => array(
        		'data' => $this->getLanguages()
        	)
        ));

		$this->updateAction("save", null, BASIC_LANGUAGE::init()->get('update'), 3);	
		$this->updateAction("list", 'ActionFormEdit');
		
		$this->delAction('add');
		$this->delAction('delete');
		$this->delAction('cancel');
		
		$this->prefix = 'p';
	}
	/**
	 * Extends parent method like set message when the profile is updated
	 * @access public
	 * @return html
	 */
	function ActionFormEdit($id = 0){
		if($id && !$this->messages){
			$this->ActionLoad($id);
			if(BASIC_SESSION::init()->get('profile_updated_success')){
				BASIC_ERROR::init()->setMessage(BASIC_LANGUAGE::init()->get('profile_updated_success'));
				BASIC_SESSION::init()->un('profile_updated_success');
			}
		} 
		return $this->FORM_MANAGER();
	}
	
	/**
	 * Save data in db
	 * @access public
	 * @return integer
	 */
	function ActionSave($id = 0){
		$id = parent::ParentActionSave($id);
		if($id){
			BASIC_SESSION::init()->set('profile_updated_success',1);
			
			
			//set user's language
			if($this->getDataBuffer('language') && $this->getDataBuffer('language') != BASIC_LANGUAGE::init()->current()){
		    	BASIC_LANGUAGE::init()->reloadLanguage($this->getDataBuffer('language'));
			}
			
		}
		return $id;
	}
}