import { faGaugeHigh, faUsers, faFolderTree, faEnvelope, faFolderOpen, faInstitution, faFolder } from "@fortawesome/free-solid-svg-icons";

export const mainSideMenu: Base.Component.Menu.MenuItem[] = [
    {
    id: "dashboard",
    icon: faGaugeHigh,
    label: "Dashboard",
    to: { name: "Dashboard" },
    child: [
      {
        id: "mainDashboard",
        icon: faGaugeHigh,
        label: "Main Dashboard",
        to: { name: "MainDashboard" },
      },
            {
        id: "documentDashboard",
        icon: faFolderOpen,
        label: "Documents Dashboard",
        to: { name: "DocumentDashboard" },
      },
      
    ]
  },
  {
    id: "user",
    icon: faUsers,
    label: "Users",
    to: { name: "IndexUser" },
  },
  {
    id: "documents",
    icon: faFolderOpen,
    label: "Documents",
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
]
