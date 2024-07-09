export default {
  methods: {
    TimeToString: function (timestamp) {
      return moment(timestamp).format("DD MMM, YYYY HH:mm");
    },
    isAdmin: function (t) {
      return t.props.auth.user.role_names.includes("Admin");
    },
    isMine: function (t, id) {
      return t.props.auth.user.id == id;
    },
  },
};
