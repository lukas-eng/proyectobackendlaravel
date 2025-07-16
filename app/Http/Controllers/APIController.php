<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Venue;

class APIController extends Controller
{
    private function validateToken(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Token no proporcionado'], 400);
        }

        $user = User::where('token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        return $user;
    }

public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $user = User::where('username', $request->username)->first();

    if (!$user || $request->password !== $user->password) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    $token = bin2hex(random_bytes(16));
    $user->token = $token;
    $user->save();

    return response()->json([
        'message' => 'Autenticación exitosa',
        'token' => $token,
        'user' => $user
    ], 200);
}

    public function logout(Request $request)
    {
        $user = $this->validateToken($request);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $user->token = null;
        $user->save();

        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }

    public function createEvent(Request $request)
    {
        $user = $this->validateToken($request);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        try {
            $event = new Event();
            $event->name = $request->name;
            $event->date = $request->date;
            $event->venue_id = $request->venue_id;
            $event->save();

            return response()->json(['message' => 'Registro exitoso del evento'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error en la solicitud'], 400);
        }
    }

    public function listEvents(Request $request)
    {
        $user = $this->validateToken($request);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $events = Event::all();
        return response()->json(['message' => "Consulta exitosa", 'data' => $events], 200);
    }

    public function editEvent(Request $request, $id)
    {
        $user = $this->validateToken($request);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $data = $request->only(['name', 'date', 'venue_id']);
        $rules = [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'venue_id' => 'required|integer'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la Solicitud', 'errors' => $validator->errors()], 400);
        }

        $updated = $event->update($data);

        if (!$updated) {
            return response()->json(['message' => 'Error al editar el evento'], 400);
        }

        return response()->json(['message' => 'Edición exitosa'], 200);
    }

    public function createParticipant(Request $request)
    {
        $user = $this->validateToken($request);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        try {
            Participant::create($request->all());
            return response()->json(['message' => 'Registro exitoso del participante'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error en la solicitud'], 400);
        }
    }

    public function listParticipantsByEvent(Request $request, $id)
    {
        $user = $this->validateToken($request);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $participants = Participant::where('event_id', $id)->get();

        if ($participants->isEmpty()) {
            return response()->json(['message' => 'Recurso no encontrado'], 404);
        }

        return response()->json(['message' => 'Consulta exitosa', 'data' => $participants], 200);
    }

    public function deleteParticipant(Request $request, $id)
    {
        $user = $this->validateToken($request);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $participant = Participant::find($id);

        if (!$participant) {
            return response()->json(['message' => 'Participante no encontrado'], 404);
        }

        try {
            $participant->delete();
            return response()->json(['message' => 'Eliminación exitosa'], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error en la solicitud'], 400);
        }
    }

    public function getVenues(Request $request)
    {
        $user = $this->validateToken($request);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $venues = Venue::all();
        return response()->json(['venues' => $venues], 200);
    }
}
