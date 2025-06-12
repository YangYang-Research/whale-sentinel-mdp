<p><strong>Regex Patterns</strong></p>
<div class="form-group">
    <label>Type</label>
    <select class="form-control" id="regex_patterns">
        <option value="">-- Select Type Attack --</option>
        <option value="sqli">SQL Injection</option>
        <option value="xss">Cross Site Scripting</option>
        <option value="unknow">Unknow</option>
    </select>
</div>

<div class="form-group">
    <label>Add Your Regex</label>
    <div class="d-flex">
        <input type="text" class="form-control mr-2" id="custom-regex-key" placeholder="Name">
        <input type="text" class="form-control mr-2" id="custom-regex-value" placeholder="Value">
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-custom-regex">Add</button>
    </div>
</div>