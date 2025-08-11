import { faGaugeHigh, faUsers, faFolderTree, faEnvelope, faFolderOpen, faInstitution, faFolder, faLink } from "@fortawesome/free-solid-svg-icons";

export const mainSideMenu: Base.Component.Menu.MenuItem[] = [
    {
    id: "dashboard",
    icon: faGaugeHigh,
    label: "Dashboard",
    to: { name: "Dashboard" },
  },
  {
    id: "user",
    icon: faUsers,
    label: "Users",
    to: { name: "IndexUser" },
  },
  {
    id: "document",
    icon: faFolderOpen,
    label: "Documents",
    to:{name:"IndexDocument"},
    child: [
      {
        id: "institutions",
        icon: faInstitution,
        label: "Institutions",
        to: { name: "IndexInstitution" },
      },
      {
        id: "classifications",
        icon: faFolderTree,
        label: "Classifications",
        to: { name: "IndexClassification" },
      },
      {
        id: "institutions",
        icon: faEnvelope,
        label: "Letters",
        to: { name: "IndexLetter" },
      },

    ]
  },
    {
    id: "shorturl",
    icon: faLink,
    label: "Short Urls",
    to: { name: "IndexShortUrl" },
  },
]
