import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import DiscussionComposer from 'flarum/forum/components/DiscussionComposer';
import Button from 'flarum/common/components/Button';
import Checkbox from 'flarum/common/components/Checkbox';
import DiscussionPage from 'flarum/forum/components/DiscussionPage';
import CheckinUploadModal from './components/CheckinUploadModal';
import CheckinHistory from './components/CheckinHistory';

app.initializers.add('flarum-checkin', () => {
  // 扩展讨论编辑器，添加打卡类型选择
  extend(DiscussionComposer.prototype, 'oninit', function () {
    if (!this.composer.fields().isCheckinType) {
      this.composer.fields().isCheckinType = false;
    }
  });

  extend(DiscussionComposer.prototype, 'headerItems', function (items) {
    if (this.composer.fields().isNew) {
      items.add(
        'checkin-type',
        <div className="CheckinTypeSelector Form-group">
          <Checkbox
            state={this.composer.fields().isCheckinType || false}
            onchange={(value) => {
              this.composer.fields().isCheckinType = value;
              m.redraw();
            }}
          >
            {app.translator.trans('flarum-checkin.forum.composer.checkin_type_label')}
          </Checkbox>
        </div>
      );
    }
  });

  // 确保在提交时包含 isCheckinType 字段
  extend(DiscussionComposer.prototype, 'data', function (data) {
    data.attributes = data.attributes || {};
    data.attributes.isCheckinType = this.composer.fields().isCheckinType || false;
  });

  // 在讨论页面添加打卡功能
  extend(DiscussionPage.prototype, 'sidebarItems', function (items) {
    const discussion = this.discussion;
    
    if (discussion.attribute('isCheckinType')) {
      items.add(
        'checkin-section',
        <div className="CheckinSection">
          <div className="CheckinSection-header">
            <h3>{app.translator.trans('flarum-checkin.forum.checkin.title')}</h3>
          </div>
          <div className="CheckinSection-content">
            <Button
              className="Button Button--primary CheckinButton"
              onclick={() => {
                app.modal.show(CheckinUploadModal, {
                  discussion: discussion,
                });
              }}
            >
              {app.translator.trans('flarum-checkin.forum.checkin.upload_button')}
            </Button>
            <CheckinHistory discussion={discussion} />
          </div>
        </div>,
        10
      );
    }
  });
});

