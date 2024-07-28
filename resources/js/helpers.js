export default {
  mounted() {
    // this.resizeTextArea($("textarea"));
  },
  methods: {
    resizeTextArea: function (element) {
      return element.height(element.scrollHeight);
    },
    TimeToString: function (timestamp) {
      return moment(timestamp).format("DD MMM, YYYY HH:mm");
    },
    isAdmin: function (t) {
      return t.props.auth.user.role_names.includes("Admin");
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
    }
  },
};
