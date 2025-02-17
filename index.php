<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>函数图像生成器</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/css/mdui.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        form {
            display: flex;
            flex-direction: row;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        form .input {
            display: flex;
        }

        .mdui-textfield,
        .mdui-select {
            margin-right: 10px;
        }

        #result {
            min-height: 400px;
        }
    </style>
</head>

<body class="mdui-theme-primary-indigo mdui-theme-accent-pink mdui-typo mdui-container">
    <h1 class="mdui-typo-display-2">函数图像生成器</h1>
    <form id="functionForm" class="mdui-form">
        <label class="mdui-textfield-label" for="type">函数类型:</label>
        <select id="type" name="type" class="mdui-select" mdui-select>
            <option value="linear">一次函数 (y = mx + b)</option>
            <option value="quadratic">二次函数 (y = mx² + bx + c)</option>
            <option value="cubic">三次函数 (y = mx³ + bx² + cx + d)</option>
            <option value="sine">正弦函数 (y = m * sin(b * x + c))</option>
            <option value="cosine">余弦函数 (y = m * cos(b * x + c))</option>
            <option value="tangent">正切函数 (y = m * tan(b * x + c))</option>
            <option value="triangle">三角波 (y = m * asin(sin(b * x + c)))</option>
            <option value="sawtooth">锯齿波 (y = m * (fmod(x + b, 2π) - π))</option>
            <option value="square">方波 (y = m * square(b * x + c))</option>
            <option value="hyperbola">双曲线函数 (y = a / x)</option>
            <option value="inverse_proportion">反比例函数 (y = a / x + b)</option>
        </select>
        <div class="input">
            <div class="mdui-textfield">
                <label class="mdui-textfield-label" for="m">m:</label>
                <input type="number" id="m" name="m" class="mdui-textfield-input">
            </div>
            <div class="mdui-textfield">
                <label class="mdui-textfield-label" for="b">b:</label>
                <input type="number" id="b" name="b" class="mdui-textfield-input">
            </div>
            <div class="mdui-textfield">
                <label class="mdui-textfield-label" for="c">c:</label>
                <input type="number" id="c" name="c" class="mdui-textfield-input">
            </div>
            <div class="mdui-textfield">
                <label class="mdui-textfield-label" for="d">d:</label>
                <input type="number" id="d" name="d" class="mdui-textfield-input">
            </div>
            <div class="mdui-textfield">
                <label class="mdui-textfield-label" for="a">a:</label>
                <input type="number" id="a" name="a" class="mdui-textfield-input">
            </div>
        </div>
        <div class="mdui-textfield">
            <button type="submit" id="submitButton" class="mdui-btn mdui-btn-raised mdui-color-theme-accent">生成图像</button>
        </div>
    </form>
    <hr>
    <div id="result"></div>
    <script src="https://cdn.jsdelivr.net/npm/mdui@1.0.1/dist/js/mdui.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#functionForm').on('submit', function(event) {
                event.preventDefault();
                $('#submitButton').attr('disabled', true);
                $.ajax({
                    url: 'fun.php',
                    type: 'GET',
                    data: $('#functionForm').serialize(),
                    success: function(response) {
                        $('#result').html('<img src="fun.php?' + $('#functionForm').serialize() + '" alt="函数图像" class="mdui-center mdui-img-fluid">');
                    },
                    complete: function() {
                        $('#submitButton').attr('disabled', false);
                    }
                });
            });
        });
        $.ajax({
            url: 'fun.php',
            type: 'GET',
            data: $('#functionForm').serialize(),
            success: function(response) {
                $('#result').html('<img src="fun.php?' + $('#functionForm').serialize() + '" alt="函数图像" class="mdui-center mdui-img-fluid">');
            },
            complete: function() {
                $('#submitButton').attr('disabled', false);
            }
        });
    </script>
</body>

</html>