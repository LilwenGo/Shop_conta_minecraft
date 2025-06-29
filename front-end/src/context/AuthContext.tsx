import { createContext, useContext, useState } from 'react';

type User = {
  id: string,
  name: string
  roles: string[]
};

type AuthContextType = {
  user: User | null
  login: (user: User) => void
  logout: () => void
  hasRole: (role: string | string[]) => boolean
  isModerator: () => boolean
  isLogued: () => boolean
  getUserId: () => string | null
};

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: React.ReactNode}) {
  const [user, setUser] = useState<User | null>(() => {
    const stored = localStorage.getItem('user');
    return stored ? JSON.parse(stored) : null;
  });

  const login = (user: User) => {
    setUser(user);
    localStorage.setItem('user', JSON.stringify(user));
  };

  const logout = () => {
    setUser(null);
    localStorage.removeItem('user');
  };

  const hasRole = (role: string | string[]) => {
    const rolesToCheck = Array.isArray(role) ? role : [role];
    return user?.roles.some((r: string) => rolesToCheck.includes(r)) ?? false;
  };

  const isModerator = () => {
    return (user?.roles.includes('Moderateur') || user?.roles.includes('Responsable')) ?? false;
  };

  const isLogued = () => {
    return user ? true : false;
  }

  const getUserId = () => {
    return user?.id ?? null;
  }

  return (
    <AuthContext.Provider value={{ user, login, logout, hasRole, isModerator, isLogued, getUserId }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth(): AuthContextType {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within an AuthProvider');
  return ctx;
}
