<div class="aps-modal active">
    <div class="aps-modal-dialog">

        <div class="aps-modal-body">
            <h3>If you have a moment, please let us know why you are deactivating:</h3>
            <ul id="reasons-list"><li class="reason" data-input-type="" data-input-placeholder=""><label><span><input type="radio" name="selected-reason" value="10"></span><span>I couldn't understand how to make it work</span></label></li><li class="reason has-input" data-input-type="textfield" data-input-placeholder="What's the plugin's name?"><label><span><input type="radio" name="selected-reason" value="2"></span><span>I found a better plugin</span></label></li><li class="reason has-input" data-input-type="textarea" data-input-placeholder="What feature?"><label><span><input type="radio" name="selected-reason" value="11"></span><span>The plugin is great, but I need specific feature that you don't support</span></label></li><li class="reason has-input" data-input-type="textarea" data-input-placeholder="Kindly share what didn't work so we can fix it for future users..."><label><span><input type="radio" name="selected-reason" value="12"></span><span>The plugin is not working</span></label></li><li class="reason has-input" data-input-type="textarea" data-input-placeholder="What you've been looking for?"><label><span><input type="radio" name="selected-reason" value="13"></span><span>It's not what I was looking for</span></label></li><li class="reason has-input" data-input-type="textarea" data-input-placeholder="What did you expect?"><label><span><input type="radio" name="selected-reason" value="14"></span><span>The plugin didn't work as expected</span></label></li><li class="reason has-input" data-input-type="textfield" data-input-placeholder=""><label><span><input type="radio" name="selected-reason" value="7"></span><span>Other</span></label></li></ul>
        </div>

        <div class="aps-modal-footer">
            <a href="#" class="button button-secondary button-deactivate allow-deactivate">Deactivate</a>
            <a href="#" class="button button-primary button-close">Cancel</a>
        </div>

    </div>
</div>

<style>
.aps-modal.active {
    display: block;
}
.aps-modal {
    position: fixed;
    overflow: auto;
    height: 100%;
    width: 100%;
    top: 0;
    z-index: 100000;
    display: none;
    background: rgba(0,0,0,0.6);
}
.aps-modal .aps-modal-dialog {
    background: transparent;
    position: absolute;
    left: 50%;
    margin-left: -298px;
    padding-bottom: 30px;
    top: -100%;
    z-index: 100001;
    width: 596px;
}
.aps-modal .aps-modal-body,
.aps-modal .aps-modal-footer {
    border: 0;
    background: #fefefe;
    padding: 20px;
}
</style>