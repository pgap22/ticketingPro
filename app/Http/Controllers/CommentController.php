<?php
declare(strict_types=1);
namespace App\Http\Controllers;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Attachment;

class CommentController extends Controller
{
    public function store(int $id): void
    {
        $uid = (int)Session::get('user_id');
        $ticket = Ticket::findOrFail($id);
        $body = trim($_POST['body'] ?? '');

        if ($body === '') {
            Session::flash('error', 'El comentario no puede estar vacío.');
            $this->redirect('/tickets/' . $id);
        }

        $user = User::with(['role','departments'])->find($uid);
        $role = $user?->role?->name ?? 'Cliente';
        $allowed = ($role === 'Administrador') ||
                   ($role === 'Agente' && in_array($ticket->department_id, $user?->departments?->pluck('id')->all() ?? [], true)) ||
                   ($role === 'Cliente' && $ticket->user_id === $uid);
        if (!$allowed) {
            Session::flash('error', 'No autorizado.');
            $this->redirect('/tickets/' . $id);
        }

        $comment = Comment::create(['ticket_id' => $id, 'user_id' => $uid, 'body' => $body]);

        // Manejar adjuntos (FilePond con storeAsFile: true => llega por $_FILES['attachments'])
        if (isset($_FILES['attachments'])) {
            $files = self::restructureFilesArray($_FILES['attachments']);
            foreach ($files as $file) {
                if ($file['error'] !== UPLOAD_ERR_OK) { continue; }

                $maxKb = (int)($_ENV['MAX_UPLOAD_KB'] ?? 2048);
                if (($file['size'] / 1024) > $maxKb) {
                    continue;
                }
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($file['tmp_name']);
                $allowed = array_map('trim', explode(',', $_ENV['UPLOAD_MIME_WHITELIST'] ?? 'image/png,image/jpeg,image/gif,application/pdf'));
                if (!in_array($mime, $allowed, true)) {
                    continue;
                }
                $safeOriginal = preg_replace('/[^A-Za-z0-9_.-]+/', '_', $file['name']);
                $ext = pathinfo($safeOriginal, PATHINFO_EXTENSION);
                $newName = uniqid('att_') . ($ext ? ('.' . $ext) : '');
                $storageDir = dirname($_SERVER['SCRIPT_FILENAME']) . '/storage/app/attachments';
                if (!is_dir($storageDir)) { @mkdir($storageDir, 0777, true); }
                $dest = $storageDir . '/' . $newName;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    Attachment::create([
                        'comment_id' => $comment->id,
                        'file_path' => 'app/attachments/' . $newName,
                        'original_name' => $safeOriginal,
                        'mime_type' => $mime,
                        'file_size_kb' => (int)ceil($file['size']/1024),
                    ]);
                }
            }
        }

        Session::flash('success', 'Comentario agregado.');
        $this->redirect('/tickets/' . $id);
    }

    /**
     * Convierte la estructura de $_FILES con multiple a un arreglo simple.
     * @param array<string,mixed> $files
     * @return array<int,array<string,mixed>>
     */
    private static function restructureFilesArray(array $files): array
    {
        $result = [];
        if (is_array($files['name'])) {
            foreach ($files['name'] as $idx => $name) {
                $result[] = [
                    'name' => $name,
                    'type' => $files['type'][$idx],
                    'tmp_name' => $files['tmp_name'][$idx],
                    'error' => $files['error'][$idx],
                    'size' => $files['size'][$idx],
                ];
            }
        } else {
            $result[] = $files;
        }
        return $result;
    }
}
