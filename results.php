<?php
// 回答データを保存するファイル名
$data_file = 'answers.txt'; // index.phpと共通の定数にするか、設定ファイルから読み込むと良い

$answers = []; // 回答データを格納する配列を初期化

// ファイルが存在する場合のみ読み込みを試みる
if (file_exists($data_file)) {
    $lines = file($data_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        $parts = explode("\t", $line);
        $answers[] = [
            $parts[0] ?? 'N/A', // 回答日時
            $parts[1] ?? 'N/A', // 理解度
            $parts[2] ?? 'N/A', // 興味点
            $parts[3] ?? 'N/A'  // 感想
        ];
    }
}

// フォームからリダイレクトされてきた場合のメッセージ表示
$message = '';
if (isset($_GET['status']) && $_GET['status'] === 'success') {
    $message = "アンケートにご協力いただきありがとうございます！";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケート結果</title>
    <style>
        /* CSSコードは元のままでOKです */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 20px auto;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-answers {
            text-align: center;
            color: #777;
            padding: 20px;
        }
        .message { /* results.phpにもメッセージ表示用のスタイルを追加 */
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>アンケート結果</h1>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (empty($answers)): ?>
            <p class="no-answers">まだ回答がありません。</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>回答日時</th>
                        <th>理解度</th>
                        <th>興味点</th>
                        <th>感想</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($answers as $answer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($answer[0]); ?></td>
                            <td><?php echo htmlspecialchars($answer[1]); ?></td>
                            <td><?php echo htmlspecialchars($answer[2]); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($answer[3])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <p style="text-align: center; margin-top: 30px;"><a href="index.php">アンケートフォームに戻る</a></p>
    </div>
</body>
</html>