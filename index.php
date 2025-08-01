<?php
// 回答データを保存するファイル名
$data_file = 'answers.txt';

// ユーザーへのメッセージを初期化
$message = '';

// フォームがPOST送信された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送信されたデータを取得し、未回答の場合は'未回答'を設定
    $q1_understanding = isset($_POST['q1_understanding']) ? $_POST['q1_understanding'] : '未回答';

    // 複数選択（チェックボックス）は配列で来るので、カンマ区切りの文字列に結合
    $q2_interest = isset($_POST['q2_interest']) ? implode(", ", $_POST['q2_interest']) : '未回答';

    // 自由記述はHTML特殊文字（<, >, &など）をエスケープしてXSS（クロスサイトスクリプティング）対策
    $q3_feedback = isset($_POST['q3_feedback']) ? htmlspecialchars($_POST['q3_feedback']) : '未回答';

    // 取得したデータをタブ区切り（\t）のログ形式で整形
    $log_entry = date('Y-m-d H:i:s') . "\t" // 回答日時
                 . "理解度: " . $q1_understanding . "\t"
                 . "興味点: " . $q2_interest . "\t"
                 . "感想: " . $q3_feedback . "\n"; // 各回答の終わりに改行

    // データをファイルに追記
    // FILE_APPEND: ファイルの末尾に追記する
    // LOCK_EX: ファイルへの同時書き込みを防ぐために排他的ロックをかける
    // PHPファイルと同じディレクトリ（student_appフォルダ）に answers.txt が作成・更新されます
    if (file_put_contents($data_file, $log_entry, FILE_APPEND | LOCK_EX) !== false) {
        $message = "アンケートにご協力いただきありがとうございます！";
    } else {
        $message = "回答の保存中にエラーが発生しました。";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>講義アンケート (PHP)</title>
    <style>
        /* ここにCSSコードを記述します。 */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            display: flex; /* Flexboxを使って中央寄せ */
            justify-content: center; /* 水平方向の中央寄せ */
            align-items: flex-start; /* 垂直方向の上寄せ */
            min-height: 100vh; /* ビューポートの高さまで広げる */
            box-sizing: border-box; /* パディングを幅に含める */
        }

        .container {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            width: 100%;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }

        p {
            color: #555;
            line-height: 1.6;
        }

        .message {
            background-color: #d4edda; /* 薄い緑色 */
            color: #155724; /* 濃い緑色 */
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .error-message {
            background-color: #f8d7da; /* 薄い赤色 */
            color: #721c24; /* 濃い赤色 */
            border: 1px solid #f5c6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }


        .question-block {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fdfdfd;
        }

        .question-block p {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .question-block label {
            display: block;
            margin-bottom: 10px;
            cursor: pointer;
            font-size: 1em;
            color: #666;
        }

        .question-block input[type="radio"],
        .question-block input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.1); /* チェックボックスやラジオボタンを少し大きく */
        }

        textarea {
            width: calc(100% - 20px); /* 左右のパディング分を引く */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            resize: vertical; /* 垂直方向のみリサイズ可能 */
            min-height: 80px;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 15px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 30px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* レスポンシブ対応 */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            h1 {
                font-size: 1.5em;
            }
            .question-block p {
                font-size: 1em;
            }
            button[type="submit"] {
                font-size: 1em;
                padding: 12px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>講義アンケート</h1>
        <p>本日の講義に関するアンケートにご協力ください。</p>

        <?php if (!empty($message)): // $message変数が空でなければ表示 ?>
            <?php if (strpos($message, 'エラー') !== false): // メッセージに「エラー」という文字列が含まれていれば赤色のスタイル ?>
                <div class="error-message"><?php echo $message; ?></div>
            <?php else: // それ以外は緑色のスタイル ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="question-block">
                <p>1. 本日の講義の理解度はどのくらいでしたか？</p>
                <label><input type="radio" name="q1_understanding" value="5" required> 5 (よく理解できた)</label><br>
                <label><input type="radio" name="q1_understanding" value="4"> 4</label><br>
                <label><input type="radio" name="q1_understanding" value="3"> 3 (普通)</label><br>
                <label><input type="radio" name="q1_understanding" value="2"> 2</label><br>
                <label><input type="radio" name="q1_understanding" value="1"> 1 (ほとんど理解できなかった)</label><br>
            </div>

            <div class="question-block">
                <p>2. 講義の中で、特に興味を持った点はどれですか？ (複数選択可)</p>
                <label><input type="checkbox" name="q2_interest[]" value="テーマA"> テーマA</label><br>
                <label><input type="checkbox" name="q2_interest[]" value="テーマB"> テーマB</label><br>
                <label><input type="checkbox" name="q2_interest[]" value="テーマC"> テーマC</label><br>
                <label><input type="checkbox" name="q2_interest[]" value="その他"> その他</label><br>
            </div>

            <div class="question-block">
                <p>3. 講義全体を通しての感想や、質問があれば記述してください。</p>
                <textarea name="q3_feedback" rows="5" placeholder="ご自由に入力してください"></textarea>
            </div>

            <button type="submit">アンケートを提出する</button>
        </form>
    </div>
</body>
</html>