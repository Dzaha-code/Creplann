<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use App\Services\WeeklyGridService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request, WeeklyGridService $weeklyGridService): JsonResponse|View
    {
        $query = $request->user()
            ->notes()
            ->with('category')
            ->latest();

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('week_date')) {
            [$weekStart, $weekEnd] = $weeklyGridService->resolveWeekRange($request->string('week_date')->toString());
            $query->whereBetween('created_at', [$weekStart->copy()->startOfDay(), $weekEnd->copy()->endOfDay()]);
        }

        $notes = $query->get();
        $categories = $request->user()->categories()->orderBy('name')->get();

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'data' => $notes,
                'categories' => $categories,
            ]);
        }

        return view('note.index', compact('notes', 'categories'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('note.index');
    }

    public function store(StoreNoteRequest $request): JsonResponse|RedirectResponse
    {
        $note = $request->user()->notes()->create($request->validated());

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'message' => 'Note berhasil dibuat.',
                'data' => $note->fresh('category'),
            ], 201);
        }

        return redirect()->route('note.index')->with('success', 'Note berhasil dibuat.');
    }

    public function show(Request $request, int $note): JsonResponse|RedirectResponse
    {
        $noteModel = $this->findOwnedNote($request, $note);

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'data' => $noteModel->load('category'),
            ]);
        }

        return redirect()->route('note.index');
    }

    public function edit(Request $request, int $note): JsonResponse|RedirectResponse
    {
        $noteModel = $this->findOwnedNote($request, $note);

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'data' => $noteModel->load('category'),
            ]);
        }

        return redirect()->route('note.index');
    }

    public function update(UpdateNoteRequest $request, int $note): JsonResponse|RedirectResponse
    {
        $noteModel = $this->findOwnedNote($request, $note);
        $noteModel->update($request->validated());

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'message' => 'Note berhasil diperbarui.',
                'data' => $noteModel->fresh('category'),
            ]);
        }

        return redirect()->route('note.index')->with('success', 'Note berhasil diperbarui.');
    }

    public function destroy(Request $request, int $note): JsonResponse|RedirectResponse
    {
        $noteModel = $this->findOwnedNote($request, $note);
        $noteModel->delete();

        if ($this->isPlannerApiRequest($request)) {
            return response()->json([
                'message' => 'Note berhasil dihapus.',
            ]);
        }

        return redirect()->route('note.index')->with('success', 'Note berhasil dihapus.');
    }

    private function findOwnedNote(Request $request, int $noteId): Note
    {
        return $request->user()
            ->notes()
            ->with('category')
            ->findOrFail($noteId);
    }

    private function isPlannerApiRequest(Request $request): bool
    {
        return $request->is('planner-api/*') || $request->expectsJson();
    }
}
