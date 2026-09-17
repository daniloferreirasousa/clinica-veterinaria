<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamRequest;
use App\Models\Animal;
use App\Models\Consultation;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get("search");

        $exams = Exam::with(['animal.tutor', 'consultation'])
            ->when($search, function ($query) use ($search){
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('laboratory', 'like', "%{$search}%")
                    ->orWhereHas('animal', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('exam_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            $search,
            $exams,
        ]);
    }

    public function optionsCreate()
    {
        $animals = Animal::with('tutor')->orderBy('name', 'asc')->get();
        $consultations = Consultation::with('animal')->orderBy('date_time', 'desc')->get();

        return response()->json([
            $animals,
            $consultations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExamRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('exams', 'public');
        }

        $exam = Exam::create($data);

        return response()->json([
            $exam,
            'success' => 'Exame cadastrado e arquivo anexado com sucesso!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        $exam->load(['animal.tutor', 'consultation']);

        return response()->json([
            $exam,
        ]);
    }

    public function optionsEdit(Exam $exam)
    {
        $animals = Animal::with('tutor')->orderBy('name', 'asc')->get();
        $consultations = Consultation::with('animal')->orderBy('date_time', 'desc')->get();

        return response()->json([
            $exam,
            $animals,
            $consultations,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExamRequest $request, Exam $exam)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            if ($exam->file_path && Storage::disk('public')->exists($exam->file_path)) {
                Storage::disk('public')->delete($exam->file_path);
            }

            $data['file_path'] = $request->file('file')->store('exams', 'public');
        }

        $exam->update($data);

        return response()->json([
            $exam,
            'success' => 'Dados do exame e anexo atualizados com sucesso!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        if ($exam->file_path && Storage::disk('public')->exists($exam->file_path)) {
            Storage::disk('public')->delete($exam->file_path);
        }

        $exam->delete();

        return response()->json([
            'success' => 'Exame e arquivo anexo foram removidos com sucesso!',
        ]);
    }
}
