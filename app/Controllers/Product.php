<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseTrait;
use App\Models\ProductModel;

class Product extends ResourceController
{
    use ResponseTrait;

    // Get All Products
    public function index()
    {
        $model = new ProductModel();
        $data['products'] = $model->findAll();
        return $this->respond($data);
    }

    // get single product
    public function show($id = null)
    {
        $model = new ProductModel();
        $data = $model->getWhere(['product_id' => $id])->getResult();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }

    // create a product
    public function create()
    {
        $model = new ProductModel();
        $data = [
            'product_name' => $this->request->getVar('product_name'),
            'product_price' => $this->request->getVar('product_price')
        ];
        $res = $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Data Saved'
            ],
            'data' => ['id'=> $res, ...$data]
        ];
        return $this->respondCreated($response);
    }

    // update product
    public function update($id = null)
    {
        $model = new ProductModel();
        $input = $this->request->getRawInput();
        $data = [
            'product_name' => $input['product_name'],
            'product_price' => $input['product_price']
        ];
        $model->update($id, $data);
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
        $model = new ProductModel();
        $data = $model->find($id);
        if($data){
            $model->delete($id);
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