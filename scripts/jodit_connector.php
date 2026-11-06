<?php
$projectRoot = dirname(__FILE__) . '/..';
chdir($projectRoot);

ini_set('display_errors', '0');
ini_set('html_errors', '0');

include_once $projectRoot . '/kernel/API.php';
sysInit();

header('Content-Type: application/json; charset=utf-8');

$joditBufferLevel = ob_get_level();
ob_start();
register_shutdown_function('jodit_handle_shutdown');

if (!sysUserIsLoggedIn()) {
  send_error(403, 'Authentication required');
}

$yearFolder = 'j' . date('Y');
$rootDir = $projectRoot . '/files/shared/file';
$baseUrl = '/files/shared/file/';
$maxFileSize = 8 * 1024 * 1024;
$allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar', 'txt');

if (!is_dir($rootDir) && !@mkdir($rootDir, 0775, true)) {
  send_error(500, 'Unable to initialize upload directory');
}

$action = isset($_REQUEST['action']) ? strtolower(trim($_REQUEST['action'])) : 'files';
$path = isset($_REQUEST['path']) ? trim($_REQUEST['path']) : '';

$path = str_replace('\\', '/', $path);
$path = preg_replace('#/+#', '/', $path);
$path = trim($path, '/');

$parts = explode('/', $path);
$safe = array();
foreach ($parts as $part) {
  if ($part === '' || $part === '.')
    continue;
  if ($part === '..') {
    array_pop($safe);
  } else {
    $safe[] = $part;
  }
}
$path = implode('/', $safe);

$targetDir = $rootDir . ($path !== '' ? '/' . $path : '');
if (!is_dir($targetDir) && !@mkdir($targetDir, 0775, true)) {
  send_error(500, 'Unable to create target directory');
}

$name = isset($_REQUEST['name']) ? trim($_REQUEST['name']) : '';
$newname = isset($_REQUEST['newname']) ? trim($_REQUEST['newname']) : '';
$from = isset($_REQUEST['from']) ? trim($_REQUEST['from']) : '';

function sanitize_filename($filename)
{
  $filename = preg_replace('/[^a-zA-Z0-9_\.\-А-Яа-яЁё]/u', '_', $filename);
  $filename = str_replace(array('..', '/', '\\'), '', $filename);
  return trim($filename, ' .');
}

switch ($action) {
  case 'permission':
  case 'permissions':
    send_success(array(
      'permissions' => array(
        'allowFiles' => true,
        'allowFileMove' => true,
        'allowFileUpload' => true,
        'allowFileUploadRemote' => false,
        'allowFileRemove' => true,
        'allowFileRename' => true,
        'allowFolders' => true,
        'allowFolderMove' => true,
        'allowFolderCreate' => true,
        'allowFolderRemove' => true,
        'allowFolderRename' => true,
        'allowImageResize' => false,
        'allowImageCrop' => false
      ),
      'code' => 220
    ));
    break;

  case 'folders':
    send_success(array(
      'sources' => array(
        build_source_payload($baseUrl, $path, array(
          'folders' => scan_folders($targetDir, $path)
        ))
      ),
      'code' => 220
    ));
    break;

  case 'fileupload':
  case 'upload':
    $uploaded = upload_files($targetDir, $allowed, $maxFileSize);
    send_success(array(
      'files' => $uploaded,
      'baseurl' => normalized_baseurl($baseUrl, $path),
      'path' => $path,
      'newfilename' => count($uploaded) ? $uploaded[0] : '',
      'code' => 220
    ));
    break;

  case 'foldercreate':
    if ($name === '') {
      send_error(400, 'Folder name is required');
    }
    $safeName = sanitize_filename($name);
    if ($safeName === '') {
      send_error(400, 'Invalid folder name');
    }
    $newDir = $targetDir . '/' . $safeName;
    if (file_exists($newDir)) {
      send_error(400, 'Directory already exists');
    }
    if (@mkdir($newDir, 0775, true)) {
      send_success(array('code' => 220));
    } else {
      send_error(500, 'Unable to create directory');
    }
    break;

  case 'folderremove':
  case 'fileremove':
    if ($name === '') {
      send_error(400, 'Name is required');
    }
    $safeName = sanitize_filename($name);
    if ($safeName === '') {
      send_error(400, 'Invalid name');
    }
    $targetPath = $targetDir . '/' . $safeName;

    if (!file_exists($targetPath)) {
      send_error(404, 'File or folder not found');
    }

    if (is_dir($targetPath)) {
      if (@rmdir($targetPath)) {
        send_success(array('code' => 220));
      } else {
        send_error(500, 'Unable to remove directory (may not be empty)');
      }
    } else {
      if (@unlink($targetPath)) {
        send_success(array('code' => 220));
      } else {
        send_error(500, 'Unable to remove file');
      }
    }
    break;

  case 'folderrename':
  case 'filerename':
    if ($name === '' || $newname === '') {
      send_error(400, 'Name and newname are required');
    }
    $safeName = sanitize_filename($name);
    $safeNewName = sanitize_filename($newname);

    if ($safeName === '' || $safeNewName === '') {
      send_error(400, 'Invalid name or newname');
    }

    $oldPath = $targetDir . '/' . $safeName;
    $newPath = $targetDir . '/' . $safeNewName;

    if (!file_exists($oldPath)) {
      send_error(404, 'File or folder not found');
    }
    if (file_exists($newPath)) {
      send_error(400, 'Target name already exists');
    }

    if (@rename($oldPath, $newPath)) {
      send_success(array('code' => 220));
    } else {
      send_error(500, 'Unable to rename');
    }
    break;

  case 'foldermove':
  case 'filemove':
    if ($from === '') {
      send_error(400, 'Source path (from) is required');
    }

    $fromPath = str_replace('\\', '/', $from);
    $fromPath = preg_replace('#/+#', '/', $fromPath);
    $fromPath = trim($fromPath, '/');
    $partsFrom = explode('/', $fromPath);
    $safeFrom = array();
    foreach ($partsFrom as $part) {
      if ($part === '' || $part === '.')
        continue;
      if ($part === '..') {
        array_pop($safeFrom);
      } else {
        $safeFrom[] = $part;
      }
    }
    $fromPath = implode('/', $safeFrom);

    $sourcePath = $rootDir . '/' . $fromPath;

    if (!file_exists($sourcePath)) {
      send_error(404, 'Source not found');
    }

    $itemName = basename($sourcePath);
    $destinationPath = $targetDir . '/' . $itemName;

    if (file_exists($destinationPath)) {
      send_error(400, 'Destination already exists');
    }

    if (@rename($sourcePath, $destinationPath)) {
      send_success(array('code' => 220));
    } else {
      send_error(500, 'Unable to move');
    }
    break;

  case 'files':
  default:
    send_success(array(
      'sources' => array(
        build_source_payload($baseUrl, $path, array(
          'files' => scan_files($targetDir)
        ))
      ),
      'code' => 220
    ));
    break;
}

function send_success($data)
{
  $payload = array(
    'success' => true,
    'time' => date('Y-m-d H:i:s'),
    'data' => $data
  );

  if (isset($data['files'])) {
    $payload['files'] = $data['files'];
    $payload['path'] = isset($data['path']) ? $data['path'] : '';
    $payload['baseurl'] = isset($data['baseurl']) ? $data['baseurl'] : '';
    $payload['error'] = 0;
    $payload['msg'] = '';
  }

  jodit_clean_output_buffer();
  echo json_encode($payload);
  exit;
}

function send_error($code, $message)
{
  header('HTTP/1.1 ' . (int) $code . ' Error');
  jodit_clean_output_buffer();
  echo json_encode(array(
    'success' => false,
    'time' => date('Y-m-d H:i:s'),
    'data' => array(
      'code' => (int) $code,
      'message' => $message
    ),
    'error' => 1,
    'msg' => $message
  ));
  exit;
}

function normalized_baseurl($baseUrl, $path)
{
  return rtrim($baseUrl . ($path ? $path . '/' : ''), '/') . '/';
}

function build_source_payload($baseUrl, $path, $extra)
{
  return array_merge(array(
    'name' => 'default',
    'baseurl' => normalized_baseurl($baseUrl, $path),
    'path' => $path
  ), $extra);
}

function scan_folders($targetDir, $path)
{
  if (!is_dir($targetDir)) {
    send_error(404, 'Folder not found');
  }

  $folders = array();
  if ($path !== '') {
    $folders[] = '..';
  }

  $list = scandir($targetDir);
  foreach ($list as $item) {
    if ($item === '.' || $item === '..') {
      continue;
    }

    if (is_dir($targetDir . '/' . $item)) {
      $folders[] = $item;
    }
  }

  return $folders;
}

function scan_files($targetDir)
{
  if (!is_dir($targetDir)) {
    send_error(404, 'Folder not found');
  }

  $files = array();
  $list = scandir($targetDir);
  foreach ($list as $item) {
    if ($item === '.' || $item === '..') {
      continue;
    }

    $full = $targetDir . '/' . $item;
    if (!is_file($full)) {
      continue;
    }

    $files[] = array(
      'file' => $item,
      'thumb' => $item,
      'changed' => date('d/m/Y H:i', filemtime($full)),
      'size' => number_format(filesize($full) / 1024, 2) . 'kB'
    );
  }

  return $files;
}

function upload_files($targetDir, $allowed, $maxFileSize)
{
  $field = detect_upload_field($_FILES);
  if ($field === null) {
    send_error(400, 'No files uploaded');
  }

  $upload = $_FILES[$field];
  $names = normalize_upload_value($upload, 'name');
  $tmpNames = normalize_upload_value($upload, 'tmp_name');
  $sizes = normalize_upload_value($upload, 'size');
  $errors = normalize_upload_value($upload, 'error');

  $uploaded = array();
  for ($i = 0; $i < count($names); $i++) {
    if (!isset($errors[$i]) || (int) $errors[$i] !== UPLOAD_ERR_OK) {
      continue;
    }

    if (!isset($sizes[$i]) || (int) $sizes[$i] > $maxFileSize) {
      continue;
    }

    $original = basename((string) $names[$i]);
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
      continue;
    }

    $safeName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $original);
    if ($safeName === '' || $safeName === '.' || $safeName === '..') {
      continue;
    }

    $destName = unique_name($targetDir, $safeName);
    $dest = $targetDir . '/' . $destName;
    if (move_uploaded_file($tmpNames[$i], $dest)) {
      $uploaded[] = $destName;
    }
  }

  return $uploaded;
}

function detect_upload_field($files)
{
  if (empty($files) || !is_array($files)) {
    return null;
  }

  if (isset($files['files'])) {
    return 'files';
  }

  if (isset($files['file'])) {
    return 'file';
  }

  foreach ($files as $field => $data) {
    if (is_array($data) && isset($data['tmp_name'])) {
      return $field;
    }
  }

  return null;
}

function normalize_upload_value($upload, $key)
{
  if (!isset($upload[$key])) {
    return array();
  }

  return is_array($upload[$key]) ? $upload[$key] : array($upload[$key]);
}

function unique_name($dir, $file)
{
  $ext = pathinfo($file, PATHINFO_EXTENSION);
  $name = pathinfo($file, PATHINFO_FILENAME);

  $candidate = $file;
  $i = 1;
  while (file_exists($dir . '/' . $candidate)) {
    $candidate = $name . '(' . $i . ')';
    if ($ext !== '') {
      $candidate .= '.' . $ext;
    }
    $i++;
  }

  return $candidate;
}

function jodit_clean_output_buffer()
{
  global $joditBufferLevel;
  $targetLevel = isset($joditBufferLevel) ? (int) $joditBufferLevel : 0;

  while (ob_get_level() > $targetLevel) {
    ob_end_clean();
  }
}

function jodit_handle_shutdown()
{
  $error = error_get_last();
  if (!empty($error) && in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
    if (!headers_sent()) {
      header('Content-Type: application/json; charset=utf-8');
    }

    jodit_clean_output_buffer();
    echo json_encode(array(
      'success' => false,
      'time' => date('Y-m-d H:i:s'),
      'data' => array(
        'code' => 500,
        'message' => 'Connector fatal error: ' . $error['message']
      ),
      'error' => 1,
      'msg' => 'Connector fatal error'
    ));
  }
}
