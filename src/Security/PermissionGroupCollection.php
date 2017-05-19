<?php

/*
	WildPHP - a modular and easily extendable IRC bot written in PHP
	Copyright (C) 2016 WildPHP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace WildPHP\Core\Security;


use Collections\Collection;
use WildPHP\Core\ComponentTrait;

class PermissionGroupCollection extends Collection
{
	use ComponentTrait;

	/**
	 * PermissionGroupCollection constructor.
	 */
	public function __construct()
	{
		parent::__construct(PermissionGroup::class);
	}

	/**
	 * @param string $name
	 *
	 * @return bool|mixed
	 */
	public function findGroupByName(string $name)
	{
		return $this->find(function (PermissionGroup $group) use ($name)
		{
			return $group->getName() == $name;
		});
	}

	/**
	 * @param string $ircAccount
	 *
	 * @return Collection
	 */
	public function findAllGroupsForIrcAccount(string $ircAccount)
	{
		return $this->findAll(function (PermissionGroup $group) use ($ircAccount)
		{
			return $group->isMemberByIrcAccount($ircAccount);
		});
	}
}