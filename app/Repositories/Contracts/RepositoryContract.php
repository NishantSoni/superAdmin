<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

Interface RepositoryContract
{
    /**
     * Method to create new record.
     *
     * @param array $attributes
     * @return collection
     */
    public function create(array $attributes);

    /**
     * Method to insert multiple records at once.
     *
     * @param array $records
     * @return mixed
     */
    public function insertMultipleRows(array $records);

    /**
     * Method to find record by its primary key.
     *
     * @param int $id
     * @return collection
     */
    public function find($id);

    /**
     * Method to update existing record.
     * It will not use "mass update" via eloquent, so that it will fire eloquent events while updating.
     *
     * @param int $id
     * @param array $attributes
     * @return boolean
     */
    public function update($id, array $attributes);

    /**
     * Method to delete a record.
     * It will not use "mass delete" via eloquent.
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id);

    /**
     * Method to check field value is exist in the table or not.
     *
     * @param array $condition
     * @return mixed
     */
    public function isFieldValueExists(array $condition);

    /**
     * Method to update/create the records.
     *
     * @param array $whereAttributes
     * @param array $insertAttributes
     * @return mixed
     */
    public function updateOrCreate(array $whereAttributes, array $insertAttributes);

    /**
     * To delete record by matching multiple attributes
     *
     * @param array $attributes
     * @return boolean
     */
    public function deleteBy(array $attributes);
}