<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

class Repository
{
    /**
     * Object of particular model
     *
     * @var object
     */
    protected $model;

    /**
     * Method to create new record.
     *
     * @param array $attributes
     * @return collection
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Method to insert multiple records at once.
     *
     * @param array $records
     * @return mixed
     */
    public function insertMultipleRows(array $records)
    {
        return $this->model->insert($records);
    }

    /**
     * Method to find record by its primary key.
     *
     * @param int $id
     * @return collection
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Method to update existing record.
     * It will not use "mass update" via eloquent, so that it will fire eloquent events while updating.
     *
     * @param int $id
     * @param array $attributes
     * @return boolean
     */
    public function update($id, array $attributes)
    {
        $currentModel = $this->find($id);

        return $currentModel->update($attributes);
    }

    /**
     * Method to delete a record.
     * It will not use "mass delete" via eloquent.
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $currentModel = $this->find($id);

        return $currentModel->delete();
    }

    /**
     * Method to check field value is exist in the table or not.
     *
     * @param array $condition
     * @return mixed
     */
    public function isFieldValueExists(array $condition)
    {
        return $this->model->where($condition)->exists();
    }

    /**
     * Method to update/create the records.
     *
     * @param array $whereAttributes
     * @param array $insertAttributes
     * @return mixed
     */
    public function updateOrCreate(array $whereAttributes, array $insertAttributes)
    {
        return $this->model->updateOrCreate($whereAttributes, $insertAttributes);
    }

    /**
     * To delete record by matching multiple attributes
     *
     * @param array $attributes
     * @return boolean
     */
    public function deleteBy(array $attributes)
    {
        return $this->model->where($attributes)->delete();
    }
}
