<?php


class AdminEmissionFactorController extends Controller {
    public function index() {
        AdminGuard::handle();
        
        $model = new EmissionFactor();
        $factors = $model->getGroupedByCategory();
        
        return $this->view('admin/emission_factors/index', [
            'factors' => $factors,
            'pageTitle' => 'Emission Factors',
            'currentPage' => 'emission-factors'
        ]);
    }

    public function store() {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $model = new EmissionFactor();
        $data = Request::all();
        $model->create($data);
        
        Session::flash('Emission factor created successfully.', 'success');
        return $this->redirect('/admin/emission-factors');
    }

    public function update($id) {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $model = new EmissionFactor();
        $data = Request::all();
        $model->update($id, $data);
        
        Session::flash('Emission factor updated successfully.', 'success');
        return $this->redirect('/admin/emission-factors');
    }

    public function delete($id) {
        AdminGuard::handle();
        CsrfMiddleware::handle();
        
        $model = new EmissionFactor();
        $model->delete($id);
        
        Session::flash('Emission factor deleted successfully.', 'success');
        return $this->redirect('/admin/emission-factors');
    }
}
