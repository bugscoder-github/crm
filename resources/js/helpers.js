let initialized = false;

export default {
  mounted() {
    if (!initialized) {
        // $('.toastsDefaultSuccess').click(this.handleToast);
        // initialized = true;
    }
  },
  methods: {
    // handleToast: function() {
    //   console.log('handleToast');
    //   $(document).Toasts('create', {
    //     class: 'bg-success',
    //     title: 'Toast Title',
    //     subtitle: 'Subtitle',
    //     body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
    //   })
    // },
    resizeTextArea: function (element) {
      return element.height(element.scrollHeight);
    },
    TimeToString: function (timestamp) {
      return moment(timestamp).format("DD MMM, YYYY HH:mm");
    },
    me: function (t) {
      return t.props.auth.user;
    },
    isAdmin: function (t) {
      return t.props.auth.user.roles[0].name == 'Admin';
    },
    isOwner: function (t) {
      return t.props.auth.user.roles[0].name == 'Owner';
    },
    isMine: function (t, id) {
      return t.props.auth.user.id == id;
    },
    toTitleCase: function (str) {
      return str.replace(
        /\w\S*/g,
        text => text.charAt(0).toUpperCase() + text.substring(1).toLowerCase()
      );
    },
    amountFormat: function (amt) {
      return amt.toFixed(2);
    },
    jsonDiff: function (newObj, oldObj) {
      const diff = {};

      for (const key in newObj) {
        if (newObj.hasOwnProperty(key) && oldObj.hasOwnProperty(key)) {
          if (newObj[key] != oldObj[key]) {
            diff[key] = {
              newValue: newObj[key],
              oldValue: oldObj[key]
            }
          }
        }
      }

      return diff;
    },
    tidyActivityLog: function (log) {
      let e = log.event.toLowerCase();
      let txt = this.toTitleCase(e);

      if (e == 'updated') {
        let diff = this.jsonDiff(log.properties.attributes, log.properties.old);
        let desc = "";
        if (Object.keys(diff).length > 0) {
          for(const x in diff) {
            desc += x+" "+diff[x].oldValue+" -> "+diff[x].newValue+"<br>";
          }
        }

        txt += "<hr>"+desc;
      }

      return txt;
    },
    getRoleClass: function (roleName) {
      var className = 'badge';
      if (roleName.toLowerCase() == "owner")   { className += ' bg-danger'; }
      if (roleName.toLowerCase() == "admin")   { className += ' bg-info'; }
      if (roleName.toLowerCase() == "sales")   { className += ' bg-success'; }
      if (roleName.toLowerCase() == "service") { className += ' bg-warning'; }
    
      return className;
    }
  },
};
