import app from 'flarum/forum/app';
import Component, { ComponentAttrs } from 'flarum/common/Component';
import Button from 'flarum/common/components/Button';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';

interface CheckinHistoryAttrs extends ComponentAttrs {
  discussion: any;
}

export default class CheckinHistory extends Component<CheckinHistoryAttrs> {
  records: any[] = [];
  loading = true;

  oninit(vnode: any) {
    super.oninit(vnode);
    this.loadRecords();
    
    // 监听打卡记录创建事件
    window.addEventListener('checkin-record-created', () => {
      this.loadRecords();
    });
  }

  view() {
    if (this.loading) {
      return <LoadingIndicator />;
    }

    if (this.records.length === 0) {
      return (
        <div className="CheckinHistory-empty">
          {app.translator.trans('flarum-checkin.forum.history.empty')}
        </div>
      );
    }

    return (
      <div className="CheckinHistory">
        <h4>{app.translator.trans('flarum-checkin.forum.history.title')}</h4>
        <ul className="CheckinHistory-list">
          {this.records.map((record) => (
            <li className="CheckinHistory-item" key={record.id()}>
              <div className="CheckinHistory-date">
                {new Date(record.attribute('checkinDate')).toLocaleDateString('zh-CN')}
              </div>
              {record.attribute('photoUrl') && (
                <div className="CheckinHistory-photo">
                  <img src={record.attribute('photoUrl')} alt="打卡照片" />
                </div>
              )}
              {record.attribute('note') && (
                <div className="CheckinHistory-note">{record.attribute('note')}</div>
              )}
              {record.relationships()?.user?.data?.id === app.session.user?.id() && (
                <Button
                  className="Button Button--link CheckinHistory-delete"
                  onclick={() => this.deleteRecord(record)}
                >
                  {app.translator.trans('flarum-checkin.forum.history.delete')}
                </Button>
              )}
            </li>
          ))}
        </ul>
      </div>
    );
  }

  loadRecords() {
    this.loading = true;
    m.redraw();

    app
      .request({
        method: 'GET',
        url: app.forum.attribute('apiUrl') + '/checkin-records',
        params: {
          filter: {
            discussionId: this.attrs.discussion.id(),
          },
        },
      })
      .then((response: any) => {
        app.store.pushPayload(response);
        // 从 store 中获取打卡记录
        const records = app.store.all('checkin-records').filter((record: any) => {
          return record.relationships()?.discussion?.data?.id === this.attrs.discussion.id();
        });
        this.records = records.sort((a: any, b: any) => {
          return new Date(b.attribute('checkinDate')).getTime() - new Date(a.attribute('checkinDate')).getTime();
        });
        this.loading = false;
        m.redraw();
      })
      .catch(() => {
        this.loading = false;
        m.redraw();
      });
  }

  deleteRecord(record: any) {
    if (!confirm(app.translator.trans('flarum-checkin.forum.history.delete_confirm'))) {
      return;
    }

    app
      .request({
        method: 'DELETE',
        url: app.forum.attribute('apiUrl') + '/checkin-records/' + record.id(),
      })
      .then(() => {
        this.loadRecords();
        app.alerts.show({ type: 'success' }, app.translator.trans('flarum-checkin.forum.history.delete_success'));
      })
      .catch((error) => {
        app.alerts.show({ type: 'error' }, error.response?.errors?.[0]?.detail || '删除失败');
      });
  }
}

