<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseTrait;

class Customer extends ResourceController
{
    use ResponseTrait;

    protected $model;

    public function __construct() {
        $this->model = new CustomerModel();
    }

    // Get All Customers => customer
    public function index()
    {
        $data = $this->model->findAll();
        return $this->respond($data);
    }

    // get single product
    public function show($id = null)
    {
        $data = $this->model->getWhere(['id' => $id])->getResult();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }

    // create a product
    public function create()
    {
        $data = [
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email')
        ];
        $id = $this->model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Data Saved'
            ],
            'data' => ['id'=> $id, ...$data]
        ];
        return $this->respondCreated($response);
    }

    // update product
    public function update($id = null)
    {
        $input = $this->request->getRawInput();
        $data = [
            'name' => $input['name'],
            'email' => $input['email']
        ];
        $this->model->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data Updated'
            ],
            'data' => ['id'=> $id, ...$data]
        ];
        return $this->respond($response);
    }

    // delete product
    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if($data){
            $this->model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }
}