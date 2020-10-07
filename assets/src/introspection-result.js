export default {
  __schema: {
    types: [
      {
        kind: 'INTERFACE',
        name: 'Notification',
        possibleTypes: [
          {
            name: 'CommonNotification'
          },
          {
            name: 'NewDocumetNotification'
          }
        ]
      },
      {
        kind: 'INTERFACE',
        name: 'Pagination',
        possibleTypes: [
          {
            name: 'NotificationPagination'
          }
        ]
      }
    ]
  }
}
