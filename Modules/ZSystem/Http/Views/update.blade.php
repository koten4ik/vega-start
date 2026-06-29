
<a href="?code=1">обновить код</a>
<br><br>
<a href="?db=1">обновить структуру базы</a>
<br><br>
<a href="?docs=1">обновить доки</a>
<br><br>

@if(\Modules\ZSupport\App\Helpers\Env::isLocal())

<a href="#" onclick="location.href = '?migration='+document.getElementById('create-migration').value  ">создать миграцию</a>
<input id="create-migration" value="add_tields_to_xxx_table" style="width: 300px;">
<br><br>

<a href="#" onclick="location.href = '?migration='+document.getElementById('create-migration1').value  ">создать миграцию</a>
<input id="create-migration1" value="create_xxx_table" style="width: 300px;">
<br><br>

<a href="#" onclick="location.href = '?filament=1&name='+document.getElementById('create-filament-name').value  ">ресурс в филамент</a>
<input id="create-filament-name" value="" style="width: 300px;">
<br><br>

@endif

@if($data['output'] != null)
    <b>Выполнено:</b><br><br>
    {!! $data['output'] !!}
@endif


