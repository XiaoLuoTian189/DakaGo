import app from 'flarum/forum/app';
import Modal, { IModalAttrs } from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import Stream from 'flarum/common/utils/Stream';
import extractText from 'flarum/common/utils/extractText';

interface CheckinUploadModalAttrs extends IModalAttrs {
  discussion: any;
}

export default class CheckinUploadModal extends Modal<CheckinUploadModalAttrs> {
  photoUrl = Stream('');
  note = Stream('');
  uploading = false;

  className() {
    return 'CheckinUploadModal Modal--small';
  }

  title() {
    return app.translator.trans('flarum-checkin.forum.upload_modal.title');
  }

  content() {
    return (
      <div className="Modal-body">
        <div className="Form-group">
          <label>{app.translator.trans('flarum-checkin.forum.upload_modal.photo_label')}</label>
          <input
            type="file"
            accept="image/*"
            className="FormControl"
            onchange={this.handleFileSelect.bind(this)}
            disabled={this.uploading}
          />
        </div>
        <div className="Form-group">
          <label>{app.translator.trans('flarum-checkin.forum.upload_modal.note_label')}</label>
          <textarea
            className="FormControl"
            value={this.note()}
            oninput={(e: Event) => {
              this.note((e.target as HTMLTextAreaElement).value);
            }}
            placeholder={extractText(app.translator.trans('flarum-checkin.forum.upload_modal.note_placeholder'))}
            disabled={this.uploading}
          />
        </div>
        <div className="Form-group">
          <Button
            className="Button Button--primary"
            type="submit"
            loading={this.uploading}
            disabled={!this.photoUrl()}
            onclick={this.onsubmit.bind(this)}
          >
            {app.translator.trans('flarum-checkin.forum.upload_modal.submit_button')}
          </Button>
        </div>
      </div>
    );
  }

  handleFileSelect(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file) return;

    this.uploading = true;
    m.redraw();

    const formData = new FormData();
    formData.append('photo', file);

    app
      .request({
        method: 'POST',
        url: app.forum.attribute('apiUrl') + '/checkin-upload',
        body: formData,
        serialize: (raw) => raw,
      })
      .then((response: any) => {
        this.photoUrl(response.data.attributes.url);
        this.uploading = false;
        m.redraw();
      })
      .catch((error) => {
        this.uploading = false;
        app.alerts.show({ type: 'error' }, error.response?.errors?.[0]?.detail || '上传失败');
        m.redraw();
      });
  }

  onsubmit() {
    if (!this.photoUrl()) {
      return;
    }

    this.uploading = true;

    app
      .request({
        method: 'POST',
        url: app.forum.attribute('apiUrl') + '/checkin-records',
        body: {
          data: {
            type: 'checkin-records',
            attributes: {
              discussionId: this.attrs.discussion.id(),
              checkinDate: new Date().toISOString().split('T')[0],
              photoUrl: this.photoUrl(),
              note: this.note(),
            },
          },
        },
      })
      .then(() => {
        this.hide();
        app.alerts.show({ type: 'success' }, app.translator.trans('flarum-checkin.forum.upload_modal.success'));
        // 触发自定义事件以刷新打卡历史
        window.dispatchEvent(new CustomEvent('checkin-record-created'));
        m.redraw();
      })
      .catch((error) => {
        this.uploading = false;
        app.alerts.show({ type: 'error' }, error.response?.errors?.[0]?.detail || '上传失败');
        m.redraw();
      });
  }
}

