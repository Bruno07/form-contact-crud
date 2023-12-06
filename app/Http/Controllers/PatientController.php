<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\RequestPatient;
use Illuminate\Contracts\View\Factory;
use App\Repositories\RepositoryPatient;
use Illuminate\Contracts\Foundation\Application;

class PatientController extends Controller
{
    /**
     * @var RepositoryPatient
     */
    private $repository;

    /**
     * PatientController constructor.
     * @param RepositoryPatient $repository
     */
    public function __construct(RepositoryPatient $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $patients = $this->repository->all();

        return view('patients.list', compact('patients'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $patient = $this->repository->find($id);

        return view('patients.show', compact('patient'));
    }

    /**
     * @param $id
     * @return string
     */
    public function showAddresses($id)
    {
        $patient = $this->repository->find($id);

        $addresses = $patient->addresses;

        return response()->json($addresses);
    }

    /**
     * @param $id
     * @return string
     */
    public function showContacts($id)
    {
        $patient = $this->repository->find($id);

        $contacts = $patient->contacts;

        return response()->json($contacts);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * @param RequestPatient $request
     * @return RedirectResponse
     */
    public function store(RequestPatient $request)
    {
        try {
            $data = $request->except('_token');

            DB::beginTransaction();

            $patient = $this->repository->savePatient($data['patient']);

            foreach ($data['addresses'] as $dataAddress)
                $this->repository->savePatientAddress($dataAddress, $patient);

            foreach ($data['contacts'] as $dataContact)
                $this->repository->savePatientContact($dataContact, $patient);

            DB::commit();

            return back()->with(['class' => 'success', 'status' => 'Paciente cadastrado com sucesso']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['class' => 'danger', 'status' => $e->getMessage()]);
        }
    }

    /**
     * @param int $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($id)
    {
        $patient = $this->repository->find($id);

        return view('patients.edit', compact('patient'));
    }

    /**
     * @param RequestPatient $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(RequestPatient $request, $id)
    {
        try {
            $data = $request->except('_token');

            DB::beginTransaction();

            $patient = $this->repository->savePatient($data['patient'], $id);

            foreach ($data['addresses'] as $dataAddress)
                $this->repository->savePatientAddress($dataAddress, $patient);

            foreach ($data['contacts'] as $dataContact)
                $this->repository->savePatientContact($dataContact, $patient);

            DB::commit();

            return back()->with(['class' => 'success', 'status' => 'Dados alterado com sucesso']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['class' => 'danger', 'status' => $e->getMessage()]);
        }
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return back();
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id)
    {
        $this->repository->restore($id);

        return back();
    }
}
