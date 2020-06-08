<?php

interface ItemsModelInterface
{

	public function getAllItems(): array;

	public function getItemDetailsById(): array;

	public function getResultSetRowCountById():int;

	public function getResultSetById():object;

	public function insert():bool;

	public function update($request_verb):bool;

	public function delete():bool;

	public function getItemTableFields(): array;

	
}